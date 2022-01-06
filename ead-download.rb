# Archon EAD Download script
# Login, load Collections list, iterate over collections, visit and download EAD files directly
# Setup:
#   - install gems
#     - gem install dotenv
#     - gem install nokogiri
#     - gem install mechanize
#   - create .env file in Archon root with values for ARCHON_USER and ARCHON_PASSWORD
#   - create ead-download/ directory in Archon root (ignored by Git)

require 'dotenv/load'
require 'date'
require 'fileutils'
require 'nokogiri'
require 'mechanize'

export_directory = "ead-download/#{Date.today.to_s}"
Dir.mkdir(export_directory) unless File.exists?(export_directory)

login_page_url = "http://scarc.library.oregonstate.edu/findingaids/?p=core/login&go="

agent = Mechanize.new
agent.get(login_page_url)

agent.page.forms[1]["ArchonLogin"] = ENV["ARCHON_USER"] || raise('ARCHON_USER value not set in .env file')
agent.page.forms[1]["ArchonPassword"] = ENV["ARCHON_PASSWORD"] || raise('ARCHON_PASSWORD value not set in .env file')

agent.page.forms[1].submit
if agent.page.body.include?("Authentication Failed") then raise('Authentication failed. Check login and password.') end
puts "Logged in"

collections_list = agent.click(agent.page.link_with(:text => /View All/))
collections_list_doc = Nokogiri::HTML(collections_list.body)

list = collections_list_doc.css("#listitemwrapper").css(".listitem")
puts list.length.to_s + " collections"

# Output collections list file with Archon IDs
File.open("#{export_directory}/collections_list.txt", "w") do |f|
  list.each do |l|
    # Extract Archon ID from link
    archon_id = l.css("a")[0]['href'].split('=').last
    f.puts("Archon ID " + archon_id + ": " + l)
  end
end

# Iterate over each collection. For testing, can change to list.take(#).each... to only do a small amount
list.each_with_index do |l, index|
  collection = agent.click(collections_list.link_with(:href => l.css("a")[0]['href']))
  ead = agent.click(collection.link_with(:text => /EAD/))

  # Extract Archon ID from link
  archon_id = l.css("a")[0]['href'].split('=').last

  puts (index + 1).to_s + ") Archon ID " + archon_id + ": " + l

  # Write out EAD XML file
  File.write("#{export_directory}/#{archon_id}.xml", ead.body)

  # Write out Collection HTML file
  File.write("#{export_directory}/#{archon_id}.html", collection.body)

  sleep 3
end
